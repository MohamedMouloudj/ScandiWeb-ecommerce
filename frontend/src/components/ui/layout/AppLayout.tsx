import { Suspense } from "react";
import { Outlet } from "react-router";
import Navbar from "@/components/ui/layout/Navbar";
import Spinner from "@/components/ui/Spinner";

export default function AppLayout() {
  return (
    <>
      <Navbar />
      <main className="app-container">
        <Suspense fallback={<Spinner />}>
          <Outlet />
        </Suspense>
      </main>
    </>
  );
}
