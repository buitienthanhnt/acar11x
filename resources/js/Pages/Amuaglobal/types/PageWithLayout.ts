import { FC, ReactNode } from 'react';

// Tạo kiểu PageWithLayout để dùng chung cho mọi trang Inertia trong dự án
export type PageWithLayout<P = {}> = FC<P> & {
    layout?: (page: ReactNode) => ReactNode;
};
