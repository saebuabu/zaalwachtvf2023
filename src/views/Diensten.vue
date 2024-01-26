<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons >
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Diensten</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Lijst</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <ion-item>
          <ion-icon aria-hidden="true"  :ios="mdIcon" :md="mdIcon"></ion-icon>
          <ion-input class="zoek-veld" placeholder=" ..."    @ionInput="filterDiensten"></ion-input>
        </ion-item>
        <ion-list>
          <ion-item v-for="item in diensten" :key="item.StartDienst">
            <ion-label>
              {{ item.StartDienst }}
            </ion-label>
            <ion-label>
              {{ item.Zaalwacht }}
            </ion-label>
          </ion-item>
        </ion-list>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonButtons, IonIcon, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, IonLabel, IonItem, IonInput } from '@ionic/vue';
import Service from '@/services/Service';
import {
  searchOutline
} from 'ionicons/icons';

export default {
    name: 'Diensten',
    components: {
        IonButtons,
        IonContent,
        IonHeader,
        IonMenuButton,
        IonPage,
        IonTitle,
        IonToolbar,
        IonLabel,
        IonItem,
        IonInput,
        IonIcon
    },
    data () {
        return  {
            diensten: [{StartDienst: '', Zaalwacht: ''}],
            dienstenCache: [{StartDienst: '', Zaalwacht: ''}],
            mdIcon: searchOutline,
        }
  },
  methods: {
    filterDiensten(event: any) {
      //reset de diensten
      this.diensten = [...this.dienstenCache];      
      //filter de diensten met de ingevoerde tekst
      this.diensten = this.diensten.filter((dienst) => {
        if (dienst.Zaalwacht == null) {
          return false;
        }
        return dienst.Zaalwacht.toLowerCase().indexOf(event.target.value.toLowerCase()) > -1;
      });
    }
  },
  async created() {
        this.diensten = await Service.getDiensten();
        this.dienstenCache = [...this.diensten];
  }
}
</script>

<style scoped>
#container {
  display: flex;
  flex-direction: column;
}


#container h2, #container h3, #container p {
  font-size: 1.0rem;
  line-height: 1.05em;
  padding: 0.4em 0 0.5em 0.4em;
  display: inline-block;
  width: auto;
}

#container p {
  width: 50vw;
}

#container .zoek-veld {
  width: 100vw;
  background-color: #aaccaa;
  margin: 0 0.3rem 0 0.3rem;
  border-radius: 15px;
}

</style>