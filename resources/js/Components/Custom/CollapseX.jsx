import React from "react";
import { Button, Card, CardBody, Collapse, Typography } from "@material-tailwind/react";

const props = {
	btnStyle: 'string',  // [w-full]: for full width of button
	cardStyle: 'string', // [w-full]: for full width of content.
	label: 'string',
};
export default function CollapseX(params) {
	const [open, setOpen] = React.useState(false);
	const toggleOpen = () => setOpen((cur) => !cur);

	return (
		<>
			<Button onClick={toggleOpen} className={params.btnStyle}>
				{params.label}
			</Button>
			<Collapse open={open}>
				<Card className={`my-4 w-8/12 ${params.cardStyle}`}>
					<CardBody>
						<Typography>
							{params.children}
						</Typography>
					</CardBody>
				</Card>
			</Collapse>
		</>
	)

}