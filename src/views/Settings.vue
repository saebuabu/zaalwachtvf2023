<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons>
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Instellingen</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Instellingen</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <ion-list>
          <ion-list-header>
            <ion-label>Dienst Notificaties</ion-label>
          </ion-list-header>

          <ion-item>
            <ion-label position="stacked">Je naam (zoals in de dienstenlijst)</ion-label>
            <ion-input
              v-model="userName"
              placeholder="Bijv. John Doe"
              @ionChange="saveUserName"
            ></ion-input>
          </ion-item>

          <ion-item>
            <ion-label>Dagelijkse notificaties (10:00 uur)</ion-label>
            <ion-toggle
              v-model="notificationsEnabled"
              @ionChange="toggleNotifications"
              :disabled="!userName"
            ></ion-toggle>
          </ion-item>

          <ion-item v-if="notificationsEnabled && userName">
            <ion-label class="ion-text-wrap">
              <p>
                <ion-icon :icon="checkmarkCircle" color="success"></ion-icon>
                Je ontvangt elke ochtend om 10:00 uur een notificatie als je die dag dienst hebt.
              </p>
            </ion-label>
          </ion-item>

          <ion-item v-if="!userName">
            <ion-label class="ion-text-wrap">
              <p>
                <ion-icon :icon="informationCircle" color="warning"></ion-icon>
                Voer eerst je naam in om notificaties te activeren.
              </p>
            </ion-label>
          </ion-item>

          <ion-item v-if="userName && !notificationsEnabled">
            <ion-label class="ion-text-wrap">
              <p>
                <ion-icon :icon="informationCircle" color="medium"></ion-icon>
                Notificaties zijn uitgeschakeld.
              </p>
            </ion-label>
          </ion-item>

          <ion-item v-if="notificationPermission === 'denied'">
            <ion-label class="ion-text-wrap" color="danger">
              <p>
                <ion-icon :icon="closeCircle" color="danger"></ion-icon>
                Browser notificaties zijn geblokkeerd. Sta notificaties toe in je browser instellingen.
              </p>
            </ion-label>
          </ion-item>
        </ion-list>

        <ion-list v-if="userName">
          <ion-list-header>
            <ion-label>Test Notificatie</ion-label>
          </ion-list-header>
          <ion-item>
            <ion-button expand="block" @click="testNotification">
              Verstuur Test Notificatie
            </ion-button>
          </ion-item>
        </ion-list>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import {
  IonButtons,
  IonContent,
  IonHeader,
  IonMenuButton,
  IonPage,
  IonTitle,
  IonToolbar,
  IonList,
  IonListHeader,
  IonItem,
  IonLabel,
  IonInput,
  IonToggle,
  IonButton,
  IonIcon,
} from "@ionic/vue";
import { checkmarkCircle, informationCircle, closeCircle } from "ionicons/icons";
import { scheduleDailyNotifications, checkUserShiftToday, sendShiftNotification } from "@/services/NotificationService";

export default {
  name: "Settings",
  components: {
    IonButtons,
    IonContent,
    IonHeader,
    IonMenuButton,
    IonPage,
    IonTitle,
    IonToolbar,
    IonList,
    IonListHeader,
    IonItem,
    IonLabel,
    IonInput,
    IonToggle,
    IonButton,
    IonIcon,
  },
  data() {
    return {
      userName: "",
      notificationsEnabled: false,
      notificationPermission: "default",
      checkmarkCircle,
      informationCircle,
      closeCircle,
    };
  },
  methods: {
    saveUserName() {
      if (this.userName) {
        localStorage.setItem("zaalwacht_userName", this.userName);
      } else {
        localStorage.removeItem("zaalwacht_userName");
        this.notificationsEnabled = false;
        localStorage.setItem("zaalwacht_notificationsEnabled", "false");
      }
    },
    async toggleNotifications() {
      if (this.notificationsEnabled) {
        // Request permission
        const permission = await this.requestNotificationPermission();
        if (permission === "granted") {
          localStorage.setItem("zaalwacht_notificationsEnabled", "true");
          this.scheduleNotifications();
        } else {
          this.notificationsEnabled = false;
          localStorage.setItem("zaalwacht_notificationsEnabled", "false");
          alert("Notificaties zijn geblokkeerd. Sta notificaties toe in je browser instellingen.");
        }
      } else {
        localStorage.setItem("zaalwacht_notificationsEnabled", "false");
        this.cancelNotifications();
      }
    },
    async requestNotificationPermission() {
      if (!("Notification" in window)) {
        alert("Deze browser ondersteunt geen notificaties.");
        return "denied";
      }

      const permission = await Notification.requestPermission();
      this.notificationPermission = permission;
      return permission;
    },
    scheduleNotifications() {
      // Schedule daily notifications at 10:00
      scheduleDailyNotifications();
      console.log("Notificaties geactiveerd voor", this.userName);
    },
    cancelNotifications() {
      console.log("Notificaties gedeactiveerd");
      // Note: We can't easily cancel setTimeout/setInterval across page reloads
      // The notification system will check localStorage on each run
    },
    async testNotification() {
      if (Notification.permission === "granted") {
        // Check if user has a shift today
        const notification = await checkUserShiftToday(this.userName);

        if (notification.hasShift) {
          sendShiftNotification(notification);
        } else {
          // Send a test notification anyway
          new Notification("Test Notificatie", {
            body: `Hoi ${this.userName}! Dit is een test notificatie. Je hebt vandaag geen dienst.`,
            icon: "/img/icons/android-chrome-192x192.png",
            badge: "/img/icons/android-chrome-192x192.png",
            tag: "test-notification",
          });
        }
      } else {
        this.requestNotificationPermission();
      }
    },
    checkNotificationPermission() {
      if ("Notification" in window) {
        this.notificationPermission = Notification.permission;
      }
    },
  },
  mounted() {
    // Load saved settings
    const savedUserName = localStorage.getItem("zaalwacht_userName");
    const savedNotificationsEnabled = localStorage.getItem("zaalwacht_notificationsEnabled");

    if (savedUserName) {
      this.userName = savedUserName;
    }

    if (savedNotificationsEnabled === "true") {
      this.notificationsEnabled = true;
    }

    this.checkNotificationPermission();
  },
};
</script>

<style scoped>
#container {
  padding: 1rem;
}

ion-item p {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin: 0.5rem 0;
}

ion-button {
  margin: 1rem;
}
</style>
