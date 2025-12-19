import React from "react";
import BaseLayout from "./BaseLayout";
import { FooterWithLogo, HomeSpeed, NavbarDark } from "./Components";

const PageLayout = ({ children }: { children: React.ReactNode }) => {
	return (
		<BaseLayout>
			<NavbarDark></NavbarDark>
			<div className="flex flex-col flex-1 w-full my-1">
				{children}
			</div>
			<HomeSpeed></HomeSpeed>
			<FooterWithLogo></FooterWithLogo>
		</BaseLayout>
	);
}

export default PageLayout;