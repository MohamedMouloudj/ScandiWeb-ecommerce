import { ApolloClient, InMemoryCache } from "@apollo/client";

const BASE_URL = import.meta.env.VITE_API_URL;

export const apolloClient = new ApolloClient({
  uri: `${BASE_URL}/graphQL`,
  cache: new InMemoryCache(),
});
