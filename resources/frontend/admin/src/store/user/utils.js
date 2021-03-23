export default function authConfig () {
  const token = localStorage.getItem('token')

  if (!token) {
    return
  }

  return {
    Authorization: `Bearer ${token}`,
  }
}
