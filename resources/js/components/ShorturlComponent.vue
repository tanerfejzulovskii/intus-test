<template>
  <div>
    <form @submit.prevent="submitUrl">
      <input type="url" v-model="url" required placeholder="Enter URL" />
      <button type="submit">Shorten URL</button>
    </form>
    <div v-if="shortUrl">
      Short URL: <a :href="shortUrl" target="_blank">{{ shortUrl }}</a>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      url: "",
      shortUrl: "",
    };
  },
  methods: {
    async submitUrl() {
      try {
        const response = await axios.post("/api/shorten", {
          url: this.url,
        });

        this.shortUrl = response.data.shortUrl;
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>
