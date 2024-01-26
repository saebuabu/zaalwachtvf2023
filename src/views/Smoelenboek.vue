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
          <ion-row>
            <ion-col v-for="smoel in smoelen" :key="smoel.title" size="6">
              <ion-label>
                <h3>{{ smoel.title }}</h3>
                <img :src="smoel.metadata.smoel.imgix_url+ '?auto=format,compress&w=200&dpr=2'" :alt="smoel.title" />
              </ion-label>
            </ion-col>
          </ion-row>
      </ion-grid>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, IonLabel } from '@ionic/vue';
import { IonCol, IonGrid, IonRow } from '@ionic/vue';
import Service from '@/services/Service';

export default {
    name: 'Smoelenboek',
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
          smoelen: [
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
          ],
        }
  },
  methods: {
  },
  async created() {
     try {
      console.log('Before fetching data');
        this.smoelen = await Service.getSmoelenboek() as any;

        //sorteer op title (naam)
        this.smoelen.sort((a: any, b: any) => {
          if (a.title < b.title) {
            return -1;
          }
          if (a.title > b.title) {
            return 1;
          }
          return 0;
        });
      } catch (error) {
        console.log(error);
      }
  }
}

</script>

<style scoped>
ion-grid {
    --ion-grid-width: 100%;
    --ion-grid-width-xs: 90%;
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

</style>
