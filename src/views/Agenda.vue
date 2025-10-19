<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons >
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>VF Agenda (komende week)</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Voorstellingen</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container">
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
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, IonLabel, IonGrid, IonRow, IonCol } from '@ionic/vue';
import Service from '@/services/Service';

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
        IonRow,
        IonCol,
        IonGrid
    },
    data () {
        return  {
            shows: [{'tijd': '', 'dag': '','naam': '', 'type': '','id': '', 'url': '', 'soldout': ''}]
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
        this.shows = await Service.getScrapedShows();
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

ion-col a {
  color:  #F58220;
  text-decoration: none;
  font-weight: 
}

</style>