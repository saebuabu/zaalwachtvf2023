<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons>
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Google Calendar</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Zaalwachtvf Google kalender</ion-title>
        </ion-toolbar>
      </ion-header>
      <div id="container">
        <iframe
          src="https://accounts.google.com/InteractiveLogin/signinchooser?continue=https%3A%2F%2Fcalendar.google.com%2Fcalendar%2Fu%2F0%2Fr&emr=1&followup=https%3A%2F%2Fcalendar.google.com%2Fcalendar%2Fu%2F0%2Fr&osid=1&passive=1209600&service=cl&ifkv=ARZ0qKLjN3h90z1CYsZAeK_qhqllr7lK4lX0FX6-yUF5axOhAOZD_wVfm2sJUBNGX6dYQVX3K6Hz&theme=mn&ddm=0&flowName=GlifWebSignIn&flowEntry=ServiceLogin"
          style="
            position: fixed;
            top: 0px;
            left: 0px;
            bottom: 0px;
            right: 0px;
            width: 100%;
            height: 100%;
            border: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            z-index: 999999;
          "
        ></iframe>
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
} from "@ionic/vue";

export default {
  name: "GoogleCalendar",
  data() {
    return {
      diensten: [],
    };
  },
  async created() {},
};
</script>

<style scoped>
#container {
  display: flex;
  flex-direction: column;
  max-width: 100vw;
}

#container h2,
#container h3,
#container p {
  font-size: 1rem;
  line-height: 1.05em;
  padding: 0.4em 0 0.5em 0.4em;
  display: inline-block;
  width: auto;
}

#container p {
  width: 50vw;
}
</style>
