/**
 * @type: itemView
 * name: note.itemView.smallCard
 */

import { NoteItemProps as ItemProps } from '@company/note';
import actionCreators from '@company/note/actions/noteItemActions';
import { connectItemView, Link } from '@metafox/core';
import {
  DotSeparator,
  FormatDate,
  ImageSkeleton,
  ItemMedia,
  ItemText,
  ItemTitle,
  ItemView
} from '@metafox/ui';
import { getImageSrc } from '@metafox/utils';
import { Skeleton } from '@mui/material';
import React from 'react';

export function LoadingSkeleton({ wrapAs, wrapProps }) {
  return (
    <ItemView testid="skeleton" wrapAs={wrapAs} wrapProps={wrapProps}>
      <ItemMedia>
        <ImageSkeleton ratio="11" />
      </ItemMedia>
      <ItemText>
        <Skeleton />
        <Skeleton width={80} />
        <Skeleton width={80} />
      </ItemText>
    </ItemView>
  );
}

export function NoteItemSmallCard({
  item,
  user,
  wrapAs,
  wrapProps
}: ItemProps) {
  if (!item) return null;

  const { creation_date, link: to } = item;

  return (
    <ItemView testid={item.resource_name} wrapAs={wrapAs} wrapProps={wrapProps}>
      <ItemMedia
        link={to}
        src={getImageSrc(item?.image)}
        alt={item.title}
        backgroundImage
      />
      <ItemText>
        <ItemTitle>
          <Link to={item.link}>{item.title}</Link>
        </ItemTitle>
        <DotSeparator sx={{ color: 'text.secondary', mt: 1 }}>
          <Link hoverCard to={user.link} children={user.full_name} />
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
export default connectItemView(NoteItemSmallCard, actionCreators);
