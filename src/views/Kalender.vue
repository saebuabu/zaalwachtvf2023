<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons >
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Kalender</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Zaalwachtvf Google kalender</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <ion-list>
          <ion-item v-for="item in items" :key="item.title">
            <ion-label>
              <h3>{{ item.title }}</h3>
              <p>{{ item.note }}</p>
            </ion-label>
          </ion-item>
        </ion-list>
      </div>
    </ion-content>
  </ion-page>
</template>
<script setup lang="ts">
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';

//import { Browser } from '@capacitor/browser';
//const openCapacitorSite = async () => {
  //opening Google calendar
  //await Browser.open({ url: 'https://calendar.google.com/calendar/embed?src=zaalwachtvf%40gmail.com&ctz=Europe%2FAmsterdam' });
//};
//openCapacitorSite();

//list of items to display in ion-list
const items = [
  { title: 'Item 1', note: 'This is item #1' },
  { title: 'Item 2', note: 'This is item #2' },
  { title: 'Item 3', note: 'This is item #3' },
  { title: 'Item 4', note: 'This is item #4' },
  { title: 'Item 5', note: 'This is item #5' },
  { title: 'Item 6', note: 'This is item #6' },
  { title: 'Item 7', note: 'This is item #7' },
  { title: 'Item 8', note: 'This is item #8' },
  { title: 'Item 9', note: 'This is item #9' },
]; 
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
  width: 70vw;
}

</style>
