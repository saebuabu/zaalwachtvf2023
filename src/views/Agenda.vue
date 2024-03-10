<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons >
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>VF Agenda</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Voorstellingen</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
        <ion-item>
          <ion-icon aria-hidden="true"  :ios="mdIcon" :md="mdIcon"></ion-icon>
          <ion-input class="zoek-veld" placeholder=" ..."    ></ion-input>
        </ion-item>
        <ion-grid :fixed="true" v-for="show in shows" :key="show.id">
          <ion-row >
              <ion-col size="2" class="dag">
                {{ show.dag  }}
              </ion-col>
              <ion-col class="tijd">
                {{ show.tijd }}
              </ion-col>
              <ion-col size="7" class="naam">
                <a target="_new" :href="makeUrl(show.url)">{{ show.naam }}</a>	
              </ion-col>
              <ion-col>
                {{ show.type  }}
              </ion-col>
              <ion-col>
                {{ show.soldout }}
              </ion-col>
          </ion-row>
        </ion-grid>
      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts">
import { IonButtons, IonIcon, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, IonLabel, IonItem, IonInput, IonGrid, IonRow, IonCol } from '@ionic/vue';
import Service from '@/services/Service';
import {
  searchOutline
} from 'ionicons/icons';

export default {
    name: 'VFAgenda',
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
        IonIcon,
        IonRow,
        IonCol,
        IonGrid
    },
    data () {
        return  {
            shows: [{'tijd': '', 'dag': '','naam': '', 'type': '','id': '', 'url': '', 'soldout': ''}],
            //dienstenCache: [{StartDienst: '', Zaalwacht: ''}],
            mdIcon: searchOutline,
        }
  },
  methods: {
      makeUrl(url: string) {
            return url;
            //let parts = url.split('/');
            //return "/Voorstelling/"+ parts[parts.length-1];
    	},
  },
  async created() {
        this.shows = await Service.getShows();
        //this.dienstenCache = [...this.diensten];
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

ion-label.dag {
  width: 5rem;
  text-align: end;
} 
ion-label.tijd {
  width: 10px!important;
  text-align: start;
  padding-left: 1rem;
} 

ion-label.naam {
  width: 20rem;
  text-align: left;
  padding-left: 1rem;
}

</style>