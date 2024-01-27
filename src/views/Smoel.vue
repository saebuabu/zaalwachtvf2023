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
        <img  :src="smoel.metadata.smoel.url" :alt="smoel.title">
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, IonLabel } from '@ionic/vue';
import { IonCol, IonGrid, IonRow } from '@ionic/vue';
import Service from '@/services/Service';
import { useRoute } from 'vue-router';

export default {
    name: 'Smoel',
    components: {
        IonButtons,
        IonContent,
        IonHeader,
        IonMenuButton,
        IonPage,
        IonTitle,
        IonToolbar,
        IonCol,
        IonGrid,
        IonRow,
        IonLabel
      },
    data () {
        return  {
          slug: "",
          smoel: 
          {
              "slug": "",
              "title": "",
              "metadata": {
                      "smoel": {
                      "url": "",
                      "imgix_url": ""
                      }
              }
            }
          ,
        }
  },
  methods: {
  },
  async created() {
    const route = useRoute();
    this.slug = route.params.slug as string;
     try {
        this.smoel = await Service.getSmoel(this.slug) as any;
      } catch (error) {
        console.log(error);
      }
  }
}

</script>

<style scoped>
ion-grid {
    --ion-grid-width: 100%;
    --ion-grid-width-xs: 100%;
    --ion-grid-width-sm: 210px;
    --ion-grid-width-md: 410px;
    --ion-grid-width-lg: 510px;
    --ion-grid-width-xl: 600px;
    margin-inline: 2px;
  }

  ion-col {
    background-color: #fff;
    border: solid 1px #fff;
    color: darkblue;
    text-align: center;
  }

  ion-page, ion-content, div#container {
    width: 100%;
    padding: 0;
    margin: 0;
  }

</style>
