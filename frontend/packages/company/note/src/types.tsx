import { BlockViewProps } from '@metafox/core';
import {
  EmbedItemInFeedItemProps,
  ItemShape,
  ItemViewProps
} from '@metafox/ui';

export interface BlogItemShape extends Omit<ItemShape, 'user'> {
  title: string;
  description: string;
  user: string;
  text: string;
  is_draft?: boolean;
  categories?: string[];
  attachments: string[];
  tags?: string[];
}

export interface BlogItemActions {
  deleteItem: () => void;
  approveItem: () => void;
}

export interface BlogItemState {
  menuOpened: boolean;
}

export type EmbedBlogItemInFeedItemProps =
  EmbedItemInFeedItemProps<BlogItemShape>;

export type BlogItemProps = ItemViewProps<
  BlogItemShape,
  BlogItemActions,
  BlogItemState
> & {
  isModalView?: boolean;
};

export interface AppState {
  entities: {
    note: Record<string, BlogItemShape>;
  };
}

export type BlogDetailViewProps = BlogItemProps & BlockViewProps;
