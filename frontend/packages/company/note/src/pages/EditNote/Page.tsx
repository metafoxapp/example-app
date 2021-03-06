/**
 * @type: route
 * name: note.edit
 * path: /note/edit/:id,/note/add
 */

import { createEditingPage } from '@metafox/framework';

export default createEditingPage({
  appName: 'note',
  resourceName: 'note',
  pageName: 'note.edit'
});
