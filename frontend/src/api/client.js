// src/api/client.js
const api = async (url, options = {}) => {
  const config = {
    credentials: "include",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...options.headers,
    },
    ...options,
  };

  const res = await fetch(`/api${url}`, config);

  if (!res.ok) {
    const error = await res.json().catch(() => ({}));
    throw new Error(error.message || "Terjadi kesalahan");
  }

  return res.json();
};

export default api;
