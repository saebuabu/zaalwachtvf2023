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
                <a :href="`/Smoel/${smoel.slug}`">
                <img :src="smoel.thumbnail" :alt="smoel.title" />
                </a>
                <h3>{{ smoel.title }}</h3>
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
              "thumbnail": "",
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
    --ion-grid-width-xs: 100%;
    --ion-grid-width-sm: 210px;
    --ion-grid-width-md: 410px;
    --ion-grid-width-lg: 510px;
    --ion-grid-width-xl: 600px;
    margin-inline: 2px;
  }

  ion-col {
    background-color: #F58220;
    border: solid 1px #F58220;
    color: darkblue;
    text-align: center;
  }

  ion-page, ion-grid, ion-content, div#container {
    width: 100%;
    padding: 0;
    margin: 0;
    background-color: #F58220;
  }
  ion-row {
    padding: 0;
    margin: 0;
    background-color: #F58220;
  }
  
  ion-label  h3 {
    color: #fff;
    font-size: 1.2em;
    font-weight: bold;
  }

  /* ion-label img should be 59% of the width of the ion-col and the images should be square (height = width) 
     and have  a rounded border
  */
  ion-label img {
    width: 70%;
    height: 80%;
    object-fit: cover;
    border-radius: 50%;
  }


</style>
