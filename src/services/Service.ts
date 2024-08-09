import axios from "axios"
import { createBucketClient } from '@cosmicjs/sdk'
import keys from './keys'

const APIKEY = keys.VUE_APP_APIKEY
const BASE_URL = keys.VUE_APP_BASE_URL
const LANGUAGE = keys.VUE_APP_LANGUAGE
const READKEY_COSMICJS = keys.VUE_APP_READKEY_COSMICJS
const GIPHY_API_KEY = keys.VUE_APP_GIPHY_API_KEY

// export default {

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
  async getShows() {
  return await axios.get(`${BASE_URL}agenda.php?api_key=${APIKEY}&language=${LANGUAGE}`)
  .then(response => {
      return response.data
  })
  .catch(error => {
      console.log(error)
  })
},
async getSmoelenboek() {
  const cosmic = createBucketClient({
    bucketSlug: 'smoelenboek-production',
    readKey: READKEY_COSMICJS
  })
  const smoelen = await cosmic.objects.find({"type": "smoelen"})
  .props("slug,title,metadata,thumbnail")
  .depth(1);

  return smoelen.objects;
  },
  async getSmoel(slug: string) {
    const cosmic = createBucketClient({
      bucketSlug: 'smoelenboek-production',
      readKey: READKEY_COSMICJS
    })
    const smoel = await cosmic.objects.findOne({
      type: "smoelen",
      slug: slug
    }).props("slug,title,metadata")
    .depth(1);

    return smoel.object;
  },
  //get just one GIF from Giphy API,searching by a string
  async getGiphyGif(search: string) {
    return await axios.get(`https://api.giphy.com/v1/gifs/search?api_key=${GIPHY_API_KEY}&q=${search}&rating=g&limit=1`)
    .then(response => {
        return response.data
    })
    .catch(error => {
        console.log(error)
    })
  },    
}

