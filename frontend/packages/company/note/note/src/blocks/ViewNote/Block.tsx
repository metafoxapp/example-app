/**
 * @type: block
 * name: note.block.noteView
 * title: Note Detail
 * keywords: note
 * description: Display note detail
 */

import { NoteDetailViewProps as Props } from '@company/note';
import actionCreators from '@company/note/actions/noteItemActions';
import { NoteDetailViewProps as ItemProps } from '@company/note/types';
import {
  connectItemView,
  connectSubject,
  createBlock,
  Link,
  useGlobal
} from '@metafox/core';
import HtmlViewer from '@metafox/html-viewer';
import { Block, BlockContent } from '@metafox/layout';
import {
  AttachmentItem,
  CategoryList,
  DotSeparator,
  DraftFlag,
  FeaturedFlag,
  FormatDate,
  ItemAction,
  ItemTitle,
  ItemUserShape,
  ItemView,
  PendingFlag,
  SponsorFlag,
  Statistic,
  UserAvatar
} from '@metafox/ui';
import { getImageSrc } from '@metafox/utils';
import { Box, styled, Typography } from '@mui/material';
import clsx from 'clsx';
import React from 'react';
import useStyles from './styles';

const AvatarWrapper = styled('div', { name: 'AvatarWrapper' })(({ theme }) => ({
  marginRight: theme.spacing(1.5)
}));

export function LoadingSkeleton({ wrapAs, wrapProps }) {
  return <ItemView testid="skeleton" wrapAs={wrapAs} wrapProps={wrapProps} />;
}

export function DetailView({
  user,
  identity,
  item,
  state,
  actions,
  handleAction,
  isModalView
}: ItemProps) {
  const { ItemActionMenu, ItemDetailInteraction, useGetItems, i18n } =
    useGlobal();
  const classes = useStyles();
  const categories = useGetItems<{ id: number; name: string }>(
    item?.categories
  );
  const attachments = useGetItems(item?.attachments);

  if (!user || !item) return null;

  const cover = getImageSrc(item?.image);

  const { is_pending, extra, tags } = item;

  return (
    <Block testid={`detailview ${item.resource_name}`}>
      <BlockContent>
        <div className={classes.root}>
          {cover ? (
            <div
              className={clsx(
                classes.bgCover,
                isModalView ? classes.modalView : null
              )}
              style={{ backgroundImage: `url(${cover})` }}
            ></div>
          ) : null}
          {is_pending && extra?.can_approve && (
            <Box sx={{ p: [2, 2, 0, 2] }}>
              <Box
                sx={{
                  borderRadius: 1,
                  height: 48,
                  width: 'auto',
                  bgcolor: 'action.selected',
                  p: 2,
                  display: 'flex',
                  justifyContent: 'space-between',
                  alignItems: 'center'
                }}
                className={classes.pendingNotice}
              >
                <Typography variant="body1" color="text.secondary">
                  {i18n.formatMessage({ id: 'note_is_waiting_approve' })}
                </Typography>
                <Box sx={{ display: 'flex' }}>
                  <div
                    className={classes.pendingButton}
                    onClick={() => actions.approveItem()}
                  >
                    {i18n.formatMessage({ id: 'approve' })}
                  </div>
                  <div
                    className={classes.pendingButton}
                    onClick={() => actions.deleteItem()}
                  >
                    {i18n.formatMessage({ id: 'delete' })}
                  </div>
                </Box>
              </Box>
            </Box>
          )}
          <div
            className={clsx(
              classes.noteViewContainer,
              (cover || isModalView) && classes.hasBgCover
            )}
          >
            <ItemAction sx={{ position: 'absolute', top: 8, right: 8 }}>
              <ItemActionMenu
                identity={identity}
                icon={'ico-dottedmore-vertical-o'}
                state={state}
                menuName="detailActionMenu"
                handleAction={handleAction}
                className={classes.actionMenu}
              />
            </ItemAction>
            <CategoryList
              to="/note/category"
              data={categories}
              sx={{ mb: 1, mr: 2 }}
            />
            <ItemTitle variant="h3" component={'div'} pr={2} showFull>
              <PendingFlag variant="itemView" value={is_pending} />
              <FeaturedFlag variant="itemView" value={item.is_featured} />
              <SponsorFlag variant="itemView" value={item.is_sponsor} />
              <DraftFlag
                value={item.is_draft}
                variant="h3"
                component="span"
                sx={{
                  verticalAlign: 'middle',
                  fontWeight: 'normal'
                }}
              />
              <Typography
                component="h1"
                variant="h3"
                sx={{
                  pr: 2.5,
                  display: { sm: 'inline', xs: 'block' },
                  mt: { sm: 0, xs: 1 },
                  verticalAlign: 'middle'
                }}
              >
                {item?.title}
              </Typography>
            </ItemTitle>
            <div className={classes.author}>
              <AvatarWrapper>
                <UserAvatar user={user as ItemUserShape} size={48} />
              </AvatarWrapper>
              <div className={classes.authorInfo}>
                <Link
                  color={'inherit'}
                  to={user.link}
                  children={user?.full_name}
                  className={classes.userName}
                  hoverCard={`/user/${user.id}`}
                />
                <DotSeparator sx={{ color: 'text.secondary', mt: 1 }}>
                  <FormatDate
                    data-testid="publishedDate"
                    value={item?.creation_date}
                    format="MMMM DD, yyyy"
                  />
                  <Statistic
                    values={item.statistic}
                    display={'total_view'}
                    component={'span'}
                    skipZero={false}
                  />
                </DotSeparator>
              </div>
            </div>
            <Box component="div" mt={3} className={classes.noteContent}>
              <HtmlViewer html={item?.text || ''} />
            </Box>
            {tags?.length > 0 ? (
              <div className={classes.tagWrapper}>
                {tags.map(tag => (
                  <div className={classes.tagItem} key={tag}>
                    <Link to={`/note/search?q=%23${tag}`}>{tag}</Link>
                  </div>
                ))}
              </div>
            ) : null}
            {attachments?.length > 0 && (
              <>
                <div className={classes.attachmentTitle}>
                  {i18n.formatMessage({ id: 'attachments' })}
                </div>
                <div className={classes.attachment}>
                  {attachments.map(item => (
                    <div
                      className={classes.attachmentItem}
                      key={item.id.toString()}
                    >
                      <AttachmentItem
                        fileName={item.file_name}
                        downloadUrl={item.download_url}
                        isImage={item.is_image}
                        fileSizeText={item.file_size_text}
                        size="large"
                        image={item?.image}
                      />
                    </div>
                  ))}
                </div>
              </>
            )}
            <ItemDetailInteraction
              identity={identity}
              state={state}
              handleAction={handleAction}
            />
          </div>
        </div>
      </BlockContent>
    </Block>
  );
}

DetailView.LoadingSkeleton = LoadingSkeleton;
DetailView.displayName = 'NoteItem_DetailView';

const Enhance = connectSubject(
  connectItemView(DetailView, actionCreators, {
    categories: true,
    attachments: true
  })
);

export default createBlock<Props>({
  extendBlock: Enhance,
  defaults: {}
});
