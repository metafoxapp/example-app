/**
 * @type: itemView
 * name: note.itemView.mainCard
 */

import actionCreators from '@company/note/actions/noteItemActions';
import { connectItemView, Link, useGlobal } from '@metafox/framework';
import { useBlock } from '@metafox/layout';
// components
import {
  CategoryList,
  DotSeparator,
  FeaturedFlag,
  FormatDate,
  Image,
  ImageSkeleton,
  ItemAction,
  ItemMedia,
  ItemText,
  ItemTitle,
  ItemView,
  SponsorFlag
} from '@metafox/ui';
import { getImageSrc } from '@metafox/utils';
import { Skeleton } from '@mui/material';
// styles
import * as React from 'react';
// types
import { NoteItemProps as ItemProps } from '../../../types';

export function LoadingSkeleton({ wrapAs, wrapProps }) {
  return (
    <ItemView testid="skeleton" wrapAs={wrapAs} wrapProps={wrapProps}>
      <ItemMedia>
        <ImageSkeleton ratio="169" />
      </ItemMedia>
      <ItemText>
        <div>
          <Skeleton width={160} />
        </div>
        <ItemTitle>
          <Skeleton width={'100%'} />
        </ItemTitle>
        <div>
          <Skeleton width={160} />
        </div>
      </ItemText>
    </ItemView>
  );
}

export function NoteItemMainCard({
  item,
  user,
  identity,
  state,
  itemProps,
  handleAction,
  wrapAs,
  wrapProps
}: ItemProps) {
  const { ItemActionMenu, getSetting, useGetItems } = useGlobal();
  const { creation_date } = item;
  const cover = getImageSrc(item?.image, '500', getSetting('note.no_image'));
  const {
    itemLinkProps,
    itemProps: { media }
  } = useBlock();

  const categories = useGetItems<{ id: number; name: string }>(item.categories);

  return (
    <ItemView testid={item.resource_name} wrapAs={wrapAs} wrapProps={wrapProps}>
      <ItemMedia src={cover} alt={item.title} backgroundImage>
        <Link to={item.link} {...itemLinkProps}>
          <Image src={cover} {...media} />
        </Link>
      </ItemMedia>
      <ItemText>
        <CategoryList to="/note/category" data={categories} sx={{ mb: 1 }} />
        <ItemTitle>
          <FeaturedFlag variant="itemView" value={item.is_featured} />
          <SponsorFlag variant="itemView" value={item.is_sponsor} />
          <Link to={item.link} {...itemLinkProps}>
            {item.title}
          </Link>
        </ItemTitle>
        {itemProps.showActionMenu ? (
          <ItemAction placement="bottom-end">
            <ItemActionMenu
              identity={identity}
              icon={'ico-dottedmore-vertical-o'}
              state={state}
              handleAction={handleAction}
            />
          </ItemAction>
        ) : null}
        <DotSeparator sx={{ color: 'text.secondary', mt: 1 }}>
          <Link
            hoverCard={`/user/${user.id}`}
            to={user.link}
            children={user.full_name}
          />
          <FormatDate
            data-testid="creationDate"
            value={creation_date}
            format="ll"
          />
        </DotSeparator>
      </ItemText>
    </ItemView>
  );
}

NoteItemMainCard.LoadingSkeleton = LoadingSkeleton;

export default connectItemView(NoteItemMainCard, actionCreators);
