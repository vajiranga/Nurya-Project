import { boot } from 'quasar/wrappers';
import axios from 'axios';

const api = axios.create({ baseURL: 'https://nurya-project-ba-git-80a5eb-a-p-y-vajiranga-pathiranas-projects.vercel.app/api' });

export default boot(({ app }) => {
  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$api = api;
});

export { api };
