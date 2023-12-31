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
        <ion-list>
          <ion-item v-for="item in diensten" :key="item.StartDienst">
            <ion-label>
              <h3>{{ item.StartDienst }}</h3>
              <p>{{ item.Zaalwacht }}</p>
            </ion-label>
          </ion-item>
        </ion-list>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import Service from '@/services/Service';

export default {
    name: 'Diensten',
    data () {
        return  {
            diensten: [],
        }
  },
  async created() {
        this.diensten = await Service.getDiensten();
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

</style>
