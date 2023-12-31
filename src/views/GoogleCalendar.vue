<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons >
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
        <iframe src="https://calendar.google.com/calendar/embed?ctz=Europe%2FAmsterdam" style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;"></iframe>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';

export default {
    name: 'GoogleCalendar',
    data () {
        return  {
            diensten: [],
           }
  },
  async created() {
  }
}
</script>

<style scoped>
#container {
  display: flex;
  flex-direction: column;
  max-width: 100vw;
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

</style>
