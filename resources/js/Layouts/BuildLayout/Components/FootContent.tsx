import { Link, } from "@inertiajs/react";
import { Typography, Button, Input } from "@material-tailwind/react";

const LINKS = ["About Us", "Careers", "Press", "Blog", "Pricing"];
const SUB_LINKS = ["Claim", "Privacy", "Terms"];
const CURRENT_YEAR = new Date().getFullYear();

function FootContent() {
  return (
    // <WhenVisible data={'foot_page'}>
      <footer className="mt-10 px-4 pt-20 bg-white ">
        <div className="container mx-auto">
          <div className="flex flex-wrap items-end justify-center gap-8 md:justify-between">
            <div className="text-center md:text-left">
              <Typography
                as="a"
                href="https://www.material-tailwind.com"
                target="_blank"
                variant="h4"
                className="mb-6"
              >
                adoc.dev global
              </Typography>
              <ul className="flex flex-wrap items-center justify-center md:justify-start">
                {LINKS.map((link, idx) => (
                  <li key={link}>
                    <Typography
                      as="a"
                      href="#"
                      className={`py-1 font-medium !text-gray-700 transition-colors hover:!text-gray-900 ${idx === 0 ? "pr-3" : "px-3"
                        }`}
                    >
                      {link}
                    </Typography>
                  </li>
                ))}
              </ul>
            </div>
            <div className="w-full sm:w-[24rem] sm:min-w-[24rem]">
              <Typography variant="h6" color="blue-gray" className="mb-3">
                Our Newsletter
              </Typography>
              <div className="flex flex-col gap-3 sm:flex-row">
                {/* @ts-ignore */}
                <Input color="gray" label="Enter your email" />
                <Button color="gray" className="flex-shrink-0">
                  subscribe
                </Button>
              </div>
            </div>
          </div>
          <div className="mt-16 flex flex-wrap items-center justify-center gap-y-4 gap-x-8 border-t border-blue-gray-50 py-6 md:justify-between">
            <Typography className="text-center font-normal !text-gray-700">
              &copy; {CURRENT_YEAR} Made with{" "}
              <a href="https://www.material-tailwind.com" target="_blank">
                Material Tailwind
              </a>{" "}
              by{" "}
              <a href="https://www.creative-tim.com" target="_blank">
                Creative Tim
              </a>
              .
            </Typography>

            <ul className="flex items-center">
              {SUB_LINKS.map((link, idx) => (
                <li key={link}>
                  <Typography
                    as="a"
                    href="#"
                    className={`py-1 font-normal !text-gray-700 transition-colors hover:!text-gray-900 ${idx === SUB_LINKS.length - 1 ? "pl-2" : "px-2"
                      }`}
                  >
                    {link}
                  </Typography>
                </li>
              ))}
               <li>
                  <Typography
                    as="a"
                    href="/langs"
                    className={`py-1 font-normal !text-gray-700 transition-colors hover:!text-gray-900 px-2`}
                  >
                    {'Language'}
                  </Typography>
                </li>

                <li>
                  <Link
                    href="/test"
                    className={`py-1 font-normal !text-gray-700 transition-colors hover:!text-red-900 px-2`}
                  >
                    {'Test screen'}
                  </Link>
                </li>
            </ul>
          </div>
        </div>
      </footer>
    // </WhenVisible>
  );
}

export default FootContent;
