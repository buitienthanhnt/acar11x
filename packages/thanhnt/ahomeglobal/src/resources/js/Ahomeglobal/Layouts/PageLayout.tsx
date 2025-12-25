import React from "react";
import BaseLayout from "./BaseLayout";
import { FooterWithLogo, HomeSpeed, NavbarDark } from "./Components";
import useMessage from '../hooks/useMessage';
import FlashMessage from "../Components/FlashMessage";

const PageLayout = ({ children }: { children: React.ReactNode }) => {
	const { error, success } = useMessage();

	return (
		<BaseLayout>
			<NavbarDark></NavbarDark>
			<div className="relative h-0 flex justify-end overflow-y-visible">
				{(error || success) && <FlashMessage message={error || success} type={error ? 'error' : 'success'}
					className="absolute top-4 flex right-0 w-auto ">
				</FlashMessage>}
			</div>
			<div className="flex flex-col flex-1 w-full my-1">
				{children}
			</div>
			<HomeSpeed></HomeSpeed>
			<FooterWithLogo></FooterWithLogo>
		</BaseLayout>
	);
}

export default PageLayout;