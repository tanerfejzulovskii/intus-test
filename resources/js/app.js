import { createApp } from "vue";
import ShorturlComponent from "./components/ShorturlComponent.vue";

const app = createApp({});
app.component("shorturl-component", ShorturlComponent);
app.mount("#app");
