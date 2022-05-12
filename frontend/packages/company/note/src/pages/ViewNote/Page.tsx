/**
 * @type: route
 * name: note.view
 * path: /note/:id(\d+)/:slug?
 */
import { createViewItemPage } from '@metafox/framework';

export default createViewItemPage({
  appName: 'note',
  resourceName: 'note',
  pageName: 'note.view'
});
