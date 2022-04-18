/**
 * @type: modalRoute
 * name: note.viewModal
 * path: /note/:id
 */

import { createViewItemModal } from '@metafox/core';

export default createViewItemModal({
  appName: 'note',
  resourceName: 'note',
  pageName: 'note.viewModal',
  component: 'note.dialog.noteView'
});
