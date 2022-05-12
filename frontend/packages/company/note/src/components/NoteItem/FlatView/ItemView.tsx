/**
 * @type: itemView
 * name: note.itemView.mainList
 */
import actionCreators from '@company/note/actions/noteItemActions';
// types
import { NoteItemProps as ItemProps } from '@company/note/types';
import { connectItemView, Link, useGlobal } from '@metafox/framework';
// components
import {
  CategoryList,
  DraftFlag,
  FeaturedFlag,
  FormatDate,
  ImageSkeleton,
  ItemAction,
  ItemMedia,
  ItemSubInfo,
  ItemSummary,
  ItemText,
  ItemTitle,
  ItemView,
  SponsorFlag,
  Statistic
} from '@metafox/ui';
import { getImageSrc } from '@metafox/utils';
import { Skeleton, styled } from '@mui/material';
import React from 'react';

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
        <ItemSummary>
          <Skeleton width={160} />
        </ItemSummary>
        <div>
          <Skeleton width={160} />
        </div>
      </ItemText>
    </ItemView>
  );
}

const FlagWrapper = styled('span', {
  name: 'FlagWrapper',
  shouldForwardProp: prop => prop !== 'isMobile'
})<{ isMobile: boolean }>(({ theme, isMobile }) => ({
  display: 'inline-flex',
  ...(isMobile && {
    display: 'flex',
    marginBottom: theme.spacing(1)
  })
}));

export function NoteItemView({
  identity,
  itemProps,
  item,
  user,
  state,
  handleAction,
  wrapAs,
  wrapProps
}: ItemProps) {
  const { ItemActionMenu, useIsMobile, useGetItems, usePageParams } =
    useGlobal();
  const { tab } = usePageParams();
  const isMobile = useIsMobile();
  const categories = useGetItems<{ id: number; name: string }>(
    item?.categories
  );

  if (!item || !user) return null;

  const { link: to, creation_date } = item;

  const cover = getImageSrc(item?.image, '500');

  return (
    <ItemView wrapAs={wrapAs} wrapProps={wrapProps} testid="note">
      <ItemMedia src={cover} link={to} alt={item.title} backgroundImage />
      <ItemText>
        <CategoryList
          to="/note/category"
          data={categories}
          sx={{ mb: { sm: 1, xs: 0 } }}
        />
        <ItemTitle>
          <FlagWrapper isMobile={isMobile}>
            <FeaturedFlag variant="itemView" value={item.is_featured} />
            <SponsorFlag variant="itemView" value={item.is_sponsor} />
          </FlagWrapper>
          <DraftFlag
            sx={{ fontWeight: 'normal' }}
            value={item.is_draft && tab !== 'draft'}
            variant="h4"
            component="span"
          />
          <Link to={item.link}>{item.title}</Link>
        </ItemTitle>
        {itemProps.showActionMenu ? (
          <ItemAction placement="top-end">
            <ItemActionMenu
              identity={identity}
              icon={'ico-dottedmore-vertical-o'}
              state={state}
              handleAction={handleAction}
            />
          </ItemAction>
        ) : null}
        <ItemSummary>{item.description}</ItemSummary>
        <ItemSubInfo sx={{ color: 'text.secondary', mt: 1 }}>
          <Link
            color="inherit"
            to={user.link}
            children={user.full_name}
            hoverCard={`/user/${user.id}`}
          />
          <FormatDate
            data-testid="creationDate"
            value={creation_date}
            format="ll"
          />
          <Statistic
            values={item.statistic}
            display={'total_view'}
            component={'span'}
            skipZero={false}
          />
        </ItemSubInfo>
      </ItemText>
    </ItemView>
  );
}

NoteItemView.LoadingSkeleton = LoadingSkeleton;

export default connectItemView(NoteItemView, actionCreators, {});
