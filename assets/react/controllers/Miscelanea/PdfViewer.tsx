/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

import { useState } from 'react';
import { Document, Page, pdfjs } from 'react-pdf';
import {
  Box,
  IconButton,
  Typography,
  Toolbar,
  AppBar,
} from '@mui/material';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import ArrowForwardIcon from '@mui/icons-material/ArrowForward';
import ZoomInIcon from '@mui/icons-material/ZoomIn';
import ZoomOutIcon from '@mui/icons-material/ZoomOut';
import 'react-pdf/dist/Page/AnnotationLayer.css'; 
import 'react-pdf/dist/Page/TextLayer.css'; 

pdfjs.GlobalWorkerOptions.workerSrc = `/build/pdf/pdf.worker.min.mjs`;

export default function PdfViewer({pdfUrl}) {
  const [numPages, setNumPages] = useState<number | null>(null);
  const [pageNumber, setPageNumber] = useState<number>(1);
  const [scale, setScale] = useState<number>(1.50); 

  const onDocumentLoadSuccess = ({ numPages }: { numPages: number }) => {
    setNumPages(numPages);
    setPageNumber(1); 
  };

  const goToPrevPage = () => {
    setPageNumber((prevPageNumber) =>
      prevPageNumber - 1 >= 1 ? prevPageNumber - 1 : 1,
    );
  };

  const goToNextPage = () => {
    setPageNumber((prevPageNumber) =>
      prevPageNumber + 1 <= (numPages || 1)
        ? prevPageNumber + 1
        : numPages || 1,
    );
  };

  const zoomIn = () => {
    setScale((prevScale) => Math.min(prevScale + 0.1, 3.0)); 
  };

  const zoomOut = () => {
    setScale((prevScale) => Math.max(prevScale - 0.1, 0.5)); 
  };

  return (
    <Box sx={{ width: '100%', height: '100%', overflow: 'auto' }}>
      <AppBar position="sticky" color="primary" sx={{ zIndex: 1, mb: 2 }}>
        <Toolbar sx={{ justifyContent: 'space-between' }}>
          <Box>
            <IconButton
              color="inherit"
              onClick={zoomOut}
              disabled={scale <= 0.5}
            >
              <ZoomOutIcon />
            </IconButton>
            <IconButton
              color="inherit"
              onClick={zoomIn}
              disabled={scale >= 3.0}
            >
              <ZoomInIcon />
            </IconButton>
          </Box>
          <Box>
            <IconButton
              color="inherit"
              onClick={goToPrevPage}
              disabled={pageNumber <= 1}
            >
              <ArrowBackIcon />
            </IconButton>
            <Typography variant="h6" component="span" sx={{ mx: 2 }}>
              Page {pageNumber} of {numPages || 'N/A'}
            </Typography>
            <IconButton
              color="inherit"
              onClick={goToNextPage}
              disabled={pageNumber >= (numPages || 1)}
            >
              <ArrowForwardIcon />
            </IconButton>
          </Box>
        </Toolbar>
      </AppBar>

      <Box
        sx={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'flex-start', // Align to top
          padding: 2,
        }}
      >
        <Document
          file={pdfUrl}
          onLoadSuccess={onDocumentLoadSuccess}
          onLoadError={(error) => console.error('Error loading PDF:', error)}
          loading={<Typography>Loading PDF...</Typography>}
          noData={<Typography>No PDF file specified.</Typography>}
        >
          {numPages && (
            <Page
              pageNumber={pageNumber}
              scale={scale}
              renderAnnotationLayer={true} 
              renderTextLayer={true} 
            />
          )}
        </Document>
      </Box>
    </Box>
  );
};