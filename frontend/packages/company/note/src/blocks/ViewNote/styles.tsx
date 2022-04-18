import { Theme } from '@mui/material';
import { createStyles, makeStyles } from '@mui/styles';

const useStyles = makeStyles(
  (theme: Theme) =>
    createStyles({
      root: {
        backgroundColor: theme.mixins.backgroundColor('paper'),
        [theme.breakpoints.down('sm')]: {
          '& $bgCover': {
            height: 179
          },
          '& $blogViewContainer': {
            borderRadius: 0,
            marginTop: '0 !important'
          },
          '& $titleWrapper': {
            flexDirection: 'column'
          },
          '& $itemFlag': {
            marginBottom: theme.spacing(1)
          }
        }
      },
      bgCover: {
        backgroundRepeat: 'no-repeat',
        backgroundPosition: 'center',
        backgroundSize: 'cover',
        height: 320
      },
      modalView: {
        marginLeft: theme.spacing(-2),
        marginRight: theme.spacing(-2)
      },
      hasBgCover: {},
      blogViewContainer: {
        width: '100%',
        marginLeft: 'auto',
        marginRight: 'auto',
        backgroundColor: theme.mixins.backgroundColor('paper'),
        padding: theme.spacing(2),
        position: 'relative',
        borderBottomLeftRadius: theme.shape.borderRadius,
        borderBottomRightRadius: theme.shape.borderRadius,
        '&.hasBgCover': {
          marginTop: 0
        }
      },
      pendingNoticeWrapper: {
        padding: theme.spacing(2, 2, 0, 2)
      },
      pendingNotice: {
        borderRadius: theme.spacing(1),
        height: 48,
        width: 'auto',
        backgroundColor: theme.palette.action.selected,
        display: 'flex',
        alignItems: 'center',
        padding: theme.spacing(2),
        justifyContent: 'space-between'
      },
      pendingTitle: {
        fontSize: theme.mixins.pxToRem(15),
        color: theme.palette.text.secondary
      },
      pendingAction: {
        display: 'flex'
      },
      pendingButton: {
        fontSize: theme.mixins.pxToRem(15),
        color: theme.palette.primary.main,
        textTransform: 'uppercase',
        marginLeft: theme.spacing(2),
        cursor: 'pointer',
        fontWeight: theme.typography.fontWeightBold
      },
      actionMenu: {
        fontSize: `${theme.mixins.pxToRem(13)} !important`
      },
      titleWrapper: {},
      itemFlag: {
        display: 'inline-flex',
        margin: theme.spacing(0, 0.5, 0, -0.5)
      },
      blogTitle: {
        fontSize: `${theme.spacing(3)} !important`,
        lineHeight: '1 !important',
        fontWeight: `${theme.typography.fontWeightBold} !important`,
        paddingRight: theme.spacing(2.5),
        display: 'inline',
        verticalAlign: 'middle'
      },
      category: {
        marginBottom: theme.spacing(1)
      },
      author: {
        marginTop: theme.spacing(2),
        display: 'flex'
      },
      authorInfo: {},
      userName: {
        fontSize: 15,
        fontWeight: 'bold',
        color: theme.palette.text.primary,
        display: 'block'
      },
      date: {
        fontSize: 13,
        color: theme.palette.text.secondary,
        marginTop: theme.spacing(0.5)
      },
      blogContent: {
        fontSize: 15,
        lineHeight: 1.33,
        marginTop: theme.spacing(3),
        '& p + p': {
          marginBottom: theme.spacing(2.5)
        }
      },
      tagWrapper: {
        marginTop: theme.spacing(4),
        display: 'flex',
        flexWrap: 'wrap'
      },
      tagItem: {
        fontSize: 13,
        fontWeight: theme.typography.fontWeightBold,
        borderRadius: 4,
        background: theme.palette.background.default,
        marginRight: theme.spacing(1),
        marginBottom: theme.spacing(1),
        padding: theme.spacing(0, 1.5),
        height: theme.spacing(3),
        lineHeight: theme.spacing(3),
        display: 'block',
        color: '#121212',
        '&:hover': {
          background: theme.palette.background.default
        }
      },
      attachmentTitle: {
        fontSize: theme.mixins.pxToRem(18),
        marginTop: theme.spacing(4),
        color: theme.palette.text.secondary,
        fontWeight: theme.typography.fontWeightBold
      },
      attachment: {
        width: '100%',
        display: 'flex',
        flexWrap: 'wrap',
        marginTop: theme.spacing(2),
        justifyContent: 'space-between'
      },
      attachmentItem: {
        marginTop: theme.spacing(2),
        flexGrow: 0,
        flexShrink: 0,
        flexBasis: 'calc(50% - 8px)',
        minWidth: 300
      }
    }),
  { name: 'MuiBlogViewDetail' }
);

export default useStyles;
