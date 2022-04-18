import { BlockViewProps } from '@metafox/core';
import {
  EmbedItemInFeedItemProps,
  ItemShape,
  ItemViewProps
} from '@metafox/ui';

export interface NoteItemShape extends Omit<ItemShape, 'user'> {
  title: string;
  description: string;
  user: string;
  text: string;
  is_draft?: boolean;
  categories?: string[];
  attachments: string[];
  tags?: string[];
}

export interface NoteItemActions {
  deleteItem: () => void;
  approveItem: () => void;
}

export interface NoteItemState {
  menuOpened: boolean;
}

export type EmbedNoteItemInFeedItemProps =
  EmbedItemInFeedItemProps<NoteItemShape>;

export type NoteItemProps = ItemViewProps<
  NoteItemShape,
  NoteItemActions,
  NoteItemState
> & {
  isModalView?: boolean;
};

export interface AppState {
  entities: {
    note: Record<string, NoteItemShape>;
  };
}

export type NoteDetailViewProps = NoteItemProps & BlockViewProps;
