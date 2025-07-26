import { NavLink } from "react-router";

export default function Error({
  error,
  setError,
}: {
  error: object;
  setError?: (error: object | null) => void;
}) {
  return (
    <div className="flex-center h-full flex-col gap-4">
      <p className="text-red-500">
        <span className="font-bold">Error:</span> {JSON.stringify(error)}
      </p>
      <NavLink to="/" onClick={() => setError?.(null)}>
        &larr; Go back
      </NavLink>
    </div>
  );
}
