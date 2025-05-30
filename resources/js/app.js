import { createApp } from 'vue';
import ChatApp from './components/ChatApp.vue';

const app = createApp({});
app.component('chat-app', ChatApp);
app.mount('#app');