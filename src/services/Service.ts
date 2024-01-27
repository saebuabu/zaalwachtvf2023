import axios from "axios"
import { createBucketClient } from '@cosmicjs/sdk'

//De basis url voor de api
const BASE_URL = "https://itstudy.eu/zaalwacht/";
//Het pad naar de afbeeldingen
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
 async getSmoelenboek() {
  const cosmic = createBucketClient({
    bucketSlug: 'smoelenboek-production',
    readKey: 'grScdv437KuTMqHj1wzr2RyLNBQg4oCeyhOqOfyCd1WLJvCF6S'
  })
  const smoelen = await cosmic.objects.find({"type": "smoelen"})
  .props("slug,title,metadata")
  .depth(1);

  return smoelen.objects;
  },
  async getSmoel(slug: string) {
    const cosmic = createBucketClient({
      bucketSlug: 'smoelenboek-production',
      readKey: 'grScdv437KuTMqHj1wzr2RyLNBQg4oCeyhOqOfyCd1WLJvCF6S'
    })
    const smoel = await cosmic.objects.findOne({
      type: "smoelen",
      slug: slug
    }).props("slug,title,metadata")
    .depth(1);

    return smoel.object;
  },  
}

