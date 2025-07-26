import { createRoot } from "react-dom/client";
import "./index.css";
import App from "./App";
import { apolloClient } from "./services/apolloClient";
import { ApolloProvider } from "@apollo/client";

createRoot(document.getElementById("root")!).render(
  <ApolloProvider client={apolloClient}>
    <App />
  </ApolloProvider>
);
