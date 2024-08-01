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
            <div class="image">
            <img  :src="smoel.metadata.smoel.url" :alt="smoel.title">
            </div>
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
  

  #container {
    display: flex;
    flex-direction: column;
  } 

.image {
  position:absolute;
  width: 60vw;
  margin-left: 5vw;
  margin-top:50px;
  -webkit-box-shadow: inset 10px 10px 10px 4px rgba(234, 180, 63, 0.6);
  border-radius: 8px;
  box-shadow: inset 4px 7px 2px 5px rgba(212, 228, 68, 0.1);
  border: 13px inset #e69f11;
}

@media screen and (max-width: 992px) {
  .image {
    width: 90vw;
    margin-left: 5vw;
  }
}



</style>
