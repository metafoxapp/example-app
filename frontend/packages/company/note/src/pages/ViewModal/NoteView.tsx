/**
 * @type: modalRoute
 * name: note.viewModal
 * path: /note/:id
 */

import { createViewItemModal } from '@metafox/framework';

export default createViewItemModal({
  appName: 'note',
  resourceName: 'note',
  pageName: 'note.viewModal',
  component: 'note.dialog.itemView'
});
