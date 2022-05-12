/**
 * @type: route
 * name: note.search
 * path: /note/search, /note/tag/:tag, /note/category/:category_id(\d+)/:slug?
 */
import { createSearchItemPage } from '@metafox/framework';

export default createSearchItemPage({
  appName: 'note',
  resourceName: 'note',
  pageName: 'note.search',
  categoryName: 'note_category'
});
