export default function ({ req, error }) {
  const ua = process.server ?
    req.headers['user-agent'].toLowerCase() :
    window.navigator.userAgent.toLowerCase()
  if (ua.indexOf('msie') !== -1 || ua.indexOf('trident') !== -1) {
        error(403, 'Internet Explorerではご利用頂けません')
  }
}
