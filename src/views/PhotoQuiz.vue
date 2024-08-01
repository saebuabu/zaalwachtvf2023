<template>
    <ion-page>
        <ion-header :translucent="true">
            <ion-toolbar>
                <ion-buttons>
                    <ion-menu-button color="primary"></ion-menu-button>
                </ion-buttons>
                <ion-title>Wie is wie?</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content :fullscreen="true">
            <ion-header collapse="condense">
                <ion-toolbar>
                    <ion-title size="large">Raad eens wie dit is?</ion-title>

                </ion-toolbar>
            </ion-header>
            <div id="container" class="container">
                <div v-if="currentPhoto < 10 && loaded">
                    <div id="timer-wrapper" class="progress">
                        <div id="timer" :style="dynamicStyle"></div>
                    </div>
                    <ion-grid>
                        <ion-row>
                            <ion-col>
                                <ion-label>
                                    <h1>Wie is dit?</h1>
                                    <img :src="photos[currentIndex].src" class="card-img-top" alt="Person Photo">
                                </ion-label>
                            </ion-col>
                        </ion-row>
                        <ion-row>
                            <ion-col>
                                <ion-label>
                                    <div class="btn-group-vertical w-100" role="group">
                                        <ion-button shape="round" v-for="(option, index) in shuffledOptions"
                                            :key="index" @click="checkAnswer(option)" class="btn btn-primary mb-2">
                                            {{ option }}
                                        </ion-button>
                                    </div>

                                </ion-label>
                            </ion-col>
                        </ion-row>
                        <ion-row>
                            <ion-col>
                                <ion-label>Score: <span class="score">{{ score }}</span>, Tijd: {{ timeLeft }} seconden
                                    <br />Vraag {{ currentPhoto + 1 }} van 10</ion-label>
                            </ion-col>
                        </ion-row>
                    </ion-grid>
                </div>
                <div v-else>
                    <ion-grid>
                        <ion-row>
                            <ion-col>
                                <ion-label class="finish">
                                    <h1>Einde spel</h1><br />
                                    <h2>Je eindscore is: {{ score }} van 10</h2>
                                    <ion-button shape="round" @click="resetGame" class="btn btn-success">Opnieuw
                                        spelen</ion-button>

                                </ion-label>
                            </ion-col>
                        </ion-row>
                    </ion-grid>
                </div>
            </div>
        </ion-content>
    </ion-page>
</template>

<script lang="ts">
import { IonButton, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, IonLabel, IonGrid, IonRow, IonCol } from '@ionic/vue';
import Service from '@/services/Service';



var tmr: number | null = null;

export default {
    name: "PhotoQuiz",
    components: {
        IonButton,
        IonContent,
        IonHeader,
        IonMenuButton,
        IonPage,
        IonTitle,
        IonToolbar,
        IonLabel,
        IonGrid,
        IonRow,
        IonCol
    },

    async created() {
        try {
            console.log('Before fetching data');
            this.smoelen = await Service.getSmoelenboek() as any;

            console.log(this.smoelen);
            //zet de smoelen over naar foto's  name komt uit title en src uit metadata.smoel.imgix_url, plak aan iedere src verder  '?auto=format,compress&w=200&dpr=2'
            this.photos = this.smoelen.map((smoel: any) => {
                return {
                    name: smoel.title,
                    src: smoel.thumbnail
                };
            });
            console.log(this.photos);

            //geef de this.currentIndex een random nummer, maar wel tussen 0 en de lengte van de photos array
            this.currentIndex = this.getRandomIndex();
            console.log(this.currentIndex);

            //vul this.options met de naam van de huidige foto en 3 andere random namen
            this.options = [this.photos[this.currentIndex].name];
            while (this.options.length < 10) {
                let randomName = this.photos[this.getRandomIndex()].name;
                if (!this.options.includes(randomName)) {
                    this.options.push(randomName);
                }
            }

 
 
        } catch (error) {
            console.log(error);
        }
        console.log('After fetching data');
        this.loaded = true;
        this.startInterval();
    },
    data() {
        return {
            smoelen: [],
            photos: [],
            loaded: false,
            score: 0,
            currentPhoto: 0,
            currentIndex: 0,
            options: [],
            secondsLeft: 5,
            seconds: 5,
        }
    },
    computed: {
        timeLeft() {
            return this.secondsLeft;
        },
        shuffledOptions() {
            return this.shuffleArray(this.options);
        },
        dynamicStyle(): { } {
            return {
                width: `${(this.secondsLeft/this.seconds) * 100}%`
            };
        }
    },
    methods: {
        startInterval() {
            if (tmr) {
                clearInterval(tmr);
            }
            tmr = setInterval(() => {
                this.secondsLeft--;
                if (this.secondsLeft === 0) {
                    clearInterval(tmr);
                    this.secondsLeft = this.seconds;
                    this.nextPhoto();
                }
            }, 5000);
        },
        shuffleArray(array: any[]) {
            let currentIndex = array.length, temporaryValue, randomIndex;
            while (0 !== currentIndex) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }
            return array;
        },
        getRandomIndex() {
            return Math.floor(Math.random() * this.photos.length);
        },
        checkAnswer(option) {
            if (option === this.photos[this.currentIndex].name) {
                this.score++;
            }
            this.nextPhoto();
        },
        nextPhoto() {
            if (this.currentPhoto < 9) {
                this.currentPhoto++;
                this.loadNewPhoto();
                this.secondsLeft = this.seconds;
                this.startInterval();
            } else {
                this.currentPhoto = 10; // Stop de quiz na 10 vragen
            }
        },
        loadNewPhoto() {
            this.currentIndex = this.getRandomIndex();
            this.options = [this.photos[this.currentIndex].name];
            while (this.options.length < 10) {
                let randomName = this.photos[this.getRandomIndex()].name;
                if (!this.options.includes(randomName)) {
                    this.options.push(randomName);
                }
            }
        },
        resetGame() {
            this.score = 0;
            this.currentPhoto = 0;
            this.secondsLeft = this.seconds;
            this.loadNewPhoto();
        }
    },
    mounted() {
    }
};
</script>

<style scoped>
.container {
    width: 100vw;
    margin: 0 auto;
}


ion-page,
ion-grid,
ion-content,
div#container {
    width: 100%;
    min-height: 90vh;
    padding: 0;
    margin: 0;
    background-color: #F58220;
}

#timer-wrapper {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: red;
    margin-top: 11px;
}

#timer {
    width: 100%;
    height: 20px;
    transition: width 5s linear;
    background-color: green;
}

#timer.startTimer {
    width: 0.1%;
}

/* content in a ion-col should be centered */
ion-col {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

ion-label img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

ion-label img {
    width: 70%;
    height: 80%;
    object-fit: cover;
    border-radius: 50%;
  }


ion-label .finish {
    text-align: center;
}

ion-label .score {
    font-size: 1.5em;
    font-weight: bold;
}

</style>