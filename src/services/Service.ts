import axios from "axios"

//De basis url voor de api
const BASE_URL = "https://itstudy.eu/zaalwacht/";
//Het pad naar de afbeeldingen
const BASE_IMG = "";
const APIKEY = "";
const LANGUAGE = "nl_NL";


export default {
    async getDiensten() {
        return await axios.get(`${BASE_URL}diensten.php?api_key=${APIKEY}&language=${LANGUAGE}`)
        .then(response => {
            return response.data
        })
        .catch(error => {
            console.log(error)
        })
    },
    baseImgUrl : () => {
        return BASE_IMG
    }
}