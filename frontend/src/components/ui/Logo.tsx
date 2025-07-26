import { NavLink } from "react-router";

export default function Logo() {
  return (
    <NavLink
      to="/"
      state={{ categoryName: "all" }}
      className="flex-1 flex-center"
    >
      <img src="/images/VSF.svg" alt="logo" className="w-8 h-8" />
    </NavLink>
  );
}
