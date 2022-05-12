/**
 * @type: dialog
 * name: note.dialog.itemView
 */

import { NoteDetailViewProps } from '@company/note/types';
import { connectItem, useGlobal } from '@metafox/framework';
import { Dialog, DialogContent, DialogTitle } from '@metafox/dialog';
import React from 'react';
import useStyles from './styles';

function NoteViewDialog({ item, identity }: NoteDetailViewProps) {
  const classes = useStyles();
  const { useDialog, jsxBackend, useIsMobile, i18n } = useGlobal();
  const { dialogProps } = useDialog();
  const DetailView = jsxBackend.get('note.block.itemView');
  const isMobile = useIsMobile();

  if (!item) return null;

  return (
    <Dialog
      {...dialogProps}
      maxWidth="xl"
      scroll="body"
      data-testid="popupViewNote"
    >
      <DialogTitle enableBack={isMobile} disableClose={isMobile}>
        {i18n.formatMessage({ id: 'note' })}
      </DialogTitle>
      <DialogContent className={classes.root}>
        <div className={classes.contentWrapper}>
          <DetailView item={item} identity={identity} isModalView />
        </div>
      </DialogContent>
    </Dialog>
  );
}

export default connectItem(NoteViewDialog);
