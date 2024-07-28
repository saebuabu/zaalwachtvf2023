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
                    <div class="card mb-3">
                        <div class="image">
                            <img :src="photos[currentIndex].src" class="card-img-top" alt="Person Photo">

                            <div class="card-body">
                                <h5 class="card-title">Wie is dit?</h5>
                                <div class="btn-group-vertical w-100" role="group">
                                    <ion-button shape="round" v-for="(option, index) in shuffledOptions" :key="index"
                                        @click="checkAnswer(option)" class="btn btn-primary mb-2">
                                        {{ option }}
                                    </ion-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>Score: {{ score }}</p>
                    <p>Vraag {{ currentPhoto + 1 }} van 10</p>
                </div>
                <div v-else>
                    <h2>Je eindscore is: {{ score }} van 10</h2>
                    <ion-button shape="round" @click="resetGame" class="btn btn-success">Opnieuw spelen</ion-button>
                </div>
            </div>
        </ion-content>
    </ion-page>
</template>

<script lang="ts">
import { IonButton, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import Service from '@/services/Service';


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
                    src: smoel.metadata.smoel.imgix_url + '?auto=format,compress&w=200&dpr=2'
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

            //zet een timer van 5 seconden om de foto te laten zien en ga dan naar de volgende foto
            setInterval(() => {
                this.nextPhoto();
            }, 4000);

        } catch (error) {
            console.log(error);
        }
        console.log('After fetching data');
        this.loaded = true;

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
        };
    },
    computed: {
        shuffledOptions() {
            return this.shuffleArray(this.options);
        }
    },
    methods: {
        shuffleArray(array) {
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
            this.loadNewPhoto();
        }
    },
    mounted() {
        this.loadNewPhoto();
    }
};
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: 0 auto;
}

.card-img-top {
    max-height: 400px;
    object-fit: cover;
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

.image {
    position: absolute;
    width: 25vw;
    margin-left: 5vw;
    margin-top: 50px;
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