/**
 * @type: route
 * name: note.my
 * path: /note/my
 */

import { createLandingPage } from '@metafox/framework';

export default createLandingPage({
  appName: 'note',
  pageName: 'note.my',
  resourceName: 'note',
  defaultTab: 'my',
  loginRequired: true
});
