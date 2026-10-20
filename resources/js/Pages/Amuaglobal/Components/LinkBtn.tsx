import { Link } from "@inertiajs/react";
import { FunctionComponent } from "react";
import { LinkComponentBaseProps } from '@inertiajs/core';

interface Props extends LinkComponentBaseProps {
  title: string;
  path: string;
  className?: string;
}
const LinkBtn: FunctionComponent<Props> = ({ path, title, className, ...props }) => {
  return (
    <Link href={path} {...props} className={`inline-block justify-center content-center bg-gray-800 text-white p-2 px-4 rounded-md uppercase font-semibold ${className}`}>{title}</Link>
  )
}

export default LinkBtn;