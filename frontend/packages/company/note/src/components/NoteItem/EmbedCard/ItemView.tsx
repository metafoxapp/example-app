/**
 * @type: embedView
 * name: note.embedItem.insideFeedItem
 */
import { EmbedBlogItemInFeedItemProps } from '@company/note';
import actionCreators from '@company/note/actions/blogItemActions';
import { connectItemView, Link } from '@metafox/core';
import {
  FeaturedFlag,
  FeedEmbedCard,
  FeedEmbedCardMedia,
  Statistic,
  TruncateText
} from '@metafox/ui';
import { getImageSrc } from '@metafox/utils';
import { Box } from '@mui/material';
import * as React from 'react';
import useStyles from './styles';

export function BlogEmbedCard({ feed, item }: EmbedBlogItemInFeedItemProps) {
  const classes = useStyles();

  if (!item) return null;

  const image = getImageSrc(item?.image);
  const link = item.link || feed.link;

  return (
    <FeedEmbedCard bottomSpacing="normal">
      {image ? (
        <FeedEmbedCardMedia image={image} mediaRatio="11" link={link} />
      ) : null}
      <div className={classes.itemInner}>
        <TruncateText variant="h4" lines={2} sx={{ mb: 1 }}>
          <Link to={link}>{item.title}</Link>
        </TruncateText>
        <TruncateText
          variant={'body1'}
          lines={3}
          component="div"
          sx={{ mb: 2, color: 'text.secondary' }}
        >
          <div dangerouslySetInnerHTML={{ __html: item.description }} />
        </TruncateText>
        <Box
          className={classes.wrapperInfoFlag}
          display="flex"
          justifyContent="space-between"
          alignItems="flex-end"
        >
          <div>
            <Statistic
              values={item.statistic}
              display="total_view"
              fontStyle="minor"
              skipZero={false}
            />
          </div>
          <div className={classes.flagWrapper}>
            {item.is_featured ? (
              <FeaturedFlag
                variant="text"
                value={item.is_featured}
                color="primary"
              />
            ) : null}
          </div>
        </Box>
      </div>
    </FeedEmbedCard>
  );
}
BlogEmbedCard.displayName = 'BlogEmbedCard';

export default connectItemView(BlogEmbedCard, actionCreators);
