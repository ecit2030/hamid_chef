import axios from 'axios';
import { i18n, setHtmlDirection } from '@/i18n';
import { route } from 'ziggy-js';

export async function switchLocale(l: 'ar' | 'en') {
  // Update i18n locale
  i18n.global.locale.value = l;

  // Update HTML direction and language attributes
  setHtmlDirection(l);

  // Persist locale preference to server
  await axios.post(route('locale.set'), { locale: l });
}