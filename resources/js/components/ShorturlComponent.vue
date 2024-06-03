<template>
  <div>
    <form @submit.prevent="submitUrl">
      <input type="url" v-model="url" required placeholder="Enter URL" />
      <button type="submit">Shorten URL</button>
    </form>
    <div v-if="shortUrl">
      Short URL: <a :href="shortUrl" target="_blank">{{ shortUrl }}</a>
    </div>
    <div v-if="unsafeUrl">
      {{ unsafeUrl }}
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
      unsafeUrl: "",
    };
  },
  methods: {
    async submitUrl() {
      try {
        const response = await axios.post("/api/shorten", {
          url: this.url,
        });

        this.unsafeUrl = "";
        this.shortUrl = response.data.shortUrl;
      } catch (error) {
        this.shortUrl = "";
        this.unsafeUrl = error.response.data.error;
      }
    },
  },
};
</script>
