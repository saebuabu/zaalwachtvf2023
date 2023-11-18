<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons >
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Smoelenboek</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Smoelenboek</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <ion-grid :fixed="true">
          <!-- 3 items per row -->
          <ion-row>
            <ion-col v-for="item in items" :key="item.title" size="3">
              <ion-label>
                <h3>{{ item.title }}</h3>
              </ion-label>
            </ion-col>
          </ion-row>
      </ion-grid>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import { IonCol, IonGrid, IonRow } from '@ionic/vue';

//variable for grid items
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
ion-grid {
    --ion-grid-width: 80%;

    --ion-grid-width-xs: 50%;
    --ion-grid-width-sm: 288px;
    --ion-grid-width-md: 384px;
    --ion-grid-width-lg: 480px;
    --ion-grid-width-xl: 570px;
    margin-inline: 2px;
  }

  ion-col {
    background-color: #5d5d13;
    border: solid 1px #fff;
    color: #fff;
    text-align: center;
  }

</style>
