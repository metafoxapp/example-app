import { Theme } from '@mui/material';
import { createStyles, makeStyles } from '@mui/styles';

export default makeStyles(
  (theme: Theme) =>
    createStyles({
      item: {
        display: 'block'
      },
      itemOuter: {
        display: 'flex',
        borderRadius: '8px',
        border: theme.mixins.border('secondary'),
        backgroundColor: theme.mixins.backgroundColor('paper'),
        overflow: 'hidden'
      },
      title: {
        '& a': {
          color: theme.palette.text.primary,
          '& h2': {
            fontWeight: theme.typography.fontWeightBold
          }
        }
      },
      description: {
        color: theme.palette.text.secondary,
        '& p': {
          margin: 0
        }
      },
      hostLink: {
        color: theme.palette.text.secondary
      },
      subInfo: {
        textTransform: 'uppercase'
      },
      itemInner: {
        flex: 1,
        minWidth: 0,
        padding: theme.spacing(3),
        display: 'flex',
        flexDirection: 'column'
      },
      wrapperInfoFlag: {
        marginTop: 'auto'
      },
      flagWrapper: {
        marginLeft: 'auto'
      }
    }),
  { name: 'MuiFeedArticleTemplate' }
);
