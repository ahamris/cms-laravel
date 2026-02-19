import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { glob } from 'glob';

// --- Configuration ---
// Parse command-line arguments
const args = process.argv.slice(2);
const includePublic = args.includes('--include-public');
const createWebP = !args.includes('--no-webp');

async function optimizeImages() {
    console.log('🖼️  Starting image optimization...');
    console.log(`- Optimizing storage files: YES`);
    console.log(`- Optimizing public files: ${includePublic ? 'YES' : 'NO'}`);
    console.log(`- Creating WebP backups: ${createWebP && includePublic ? 'YES (for public files)' : 'NO'}`);
    console.log('⚠️  This will overwrite original files with optimized versions!\n');

    // Track sizes before and after
    const fileSizes = new Map();

    // Optimize JPEGs - overwrite originals (.jpg and .jpeg)
    console.log('📸 Optimizing JPEG images...');
    const jpegGlobs = [
        ...await glob('storage/app/public/**/*.jpg'),
        ...await glob('storage/app/public/**/*.jpeg')
    ];
    if (includePublic) {
        jpegGlobs.push(
            ...await glob('public/frontend/**/*.jpg'),
            ...await glob('public/frontend/**/*.jpeg'),
            ...await glob('public/front/**/*.jpg'),
            ...await glob('public/front/**/*.jpeg'),
            ...await glob('public/images/**/*.jpg'),
            ...await glob('public/images/**/*.jpeg'),
            ...await glob('public/assets/**/*.jpg'),
            ...await glob('public/assets/**/*.jpeg')
        );
    }

    const jpegFiles = [...new Set(jpegGlobs)];
    let jpegCount = 0;
    let jpegErrors = 0;
    let jpegTotalSaved = 0;
    
    for (const file of jpegFiles) {
        try {
            // Get original size
            const originalSize = fs.statSync(file).size;
            fileSizes.set(file, { original: originalSize, optimized: 0 });
            
            // Create temporary file
            const tempFile = file + '.tmp';
            
            // Optimize to temporary file
            await sharp(file)
                .jpeg({
                    quality: 75,
                    progressive: true,
                    mozjpeg: true
                })
                .toFile(tempFile);
            
            // Get optimized size
            const optimizedSize = fs.statSync(tempFile).size;
            fileSizes.set(file, { original: originalSize, optimized: optimizedSize });
            
            // Replace original with optimized
            fs.renameSync(tempFile, file);
            
            jpegTotalSaved += (originalSize - optimizedSize);
            jpegCount++;
        } catch (error) {
            // Clean up temp file if it exists
            const tempFile = file + '.tmp';
            if (fs.existsSync(tempFile)) {
                fs.unlinkSync(tempFile);
            }
            console.warn(`⚠️  Skipped ${file}: ${error.message}`);
            jpegErrors++;
        }
    }
    console.log(`✅ Optimized ${jpegCount} JPEG files${jpegErrors > 0 ? ` (${jpegErrors} skipped)` : ''}`);

    // Optimize PNGs - overwrite originals
    console.log('🎨 Optimizing PNG images...');
    const pngGlobs = [
        ...await glob('storage/app/public/**/*.png')
    ];
    if (includePublic) {
        pngGlobs.push(
            ...await glob('public/frontend/**/*.png'),
            ...await glob('public/assets/logo/*.png'),
            ...await glob('public/assets/**/*.png'),
            ...await glob('public/front/**/*.png'),
            ...await glob('public/images/**/*.png')
        );
    }

    const pngFiles = [...new Set(pngGlobs)];
    let pngCount = 0;
    let pngErrors = 0;
    let pngTotalSaved = 0;
    
    for (const file of pngFiles) {
        try {
            // Get original size
            const originalSize = fs.statSync(file).size;
            fileSizes.set(file, { original: originalSize, optimized: 0 });
            
            // Create temporary file
            const tempFile = file + '.tmp';
            
            // Optimize to temporary file
            await sharp(file)
                .png({
                    quality: 80,
                    compressionLevel: 9,
                    palette: true
                })
                .toFile(tempFile);
            
            // Get optimized size
            const optimizedSize = fs.statSync(tempFile).size;
            fileSizes.set(file, { original: originalSize, optimized: optimizedSize });
            
            // Replace original with optimized
            fs.renameSync(tempFile, file);
            
            pngTotalSaved += (originalSize - optimizedSize);
            pngCount++;
        } catch (error) {
            // Clean up temp file if it exists
            const tempFile = file + '.tmp';
            if (fs.existsSync(tempFile)) {
                fs.unlinkSync(tempFile);
            }
            console.warn(`⚠️  Skipped ${file}: ${error.message}`);
            pngErrors++;
        }
    }
    console.log(`✅ Optimized ${pngCount} PNG files${pngErrors > 0 ? ` (${pngErrors} skipped)` : ''}`);

    // Create WebP versions for modern browsers (storage + optionally public when includePublic)
    if (createWebP) {
        const imageFiles = [];
        // Storage: always create WebP for optimized storage images (used by blog, solutions, etc.)
        imageFiles.push(
            ...jpegFiles,
            ...pngFiles
        );
        if (includePublic) {
            imageFiles.push(
                ...await glob('public/frontend/**/*.{jpg,jpeg,png}'),
                ...await glob('public/assets/logo/*.png'),
                ...await glob('public/front/**/*.{jpg,jpeg,png}'),
                ...await glob('public/images/**/*.{jpg,jpeg,png}')
            );
        }
        const uniqueFiles = [...new Set(imageFiles)];
        console.log(`🌐 Creating WebP versions (${uniqueFiles.length} files)...`);

        let webpCount = 0;
        let webpErrors = 0;

        for (const file of uniqueFiles) {
            try {
                const fileDir = path.dirname(file);
                const webpDir = path.join(fileDir, 'webp');

                if (!fs.existsSync(webpDir)) {
                    fs.mkdirSync(webpDir, { recursive: true });
                }

                const filename = path.basename(file, path.extname(file)) + '.webp';
                const webpPath = path.join(webpDir, filename);

                await sharp(file)
                    .webp({
                        quality: 80,
                        effort: 6
                    })
                    .toFile(webpPath);

                webpCount++;
            } catch (error) {
                console.warn(`⚠️  Skipped WebP for ${file}: ${error.message}`);
                webpErrors++;
            }
        }
        console.log(`✅ Created ${webpCount} WebP backup files${webpErrors > 0 ? ` (${webpErrors} skipped)` : ''}`);
    } else {
        console.log('⏭️  Skipping WebP backup creation.');
    }

    // Calculate total savings
    let totalOriginalSize = 0;
    let totalOptimizedSize = 0;
    
    fileSizes.forEach((sizes, file) => {
        totalOriginalSize += sizes.original;
        totalOptimizedSize += sizes.optimized;
    });

    const totalSaved = totalOriginalSize - totalOptimizedSize;
    const savingsPercent = totalOriginalSize > 0 ? ((totalSaved / totalOriginalSize) * 100).toFixed(1) : 0;

    if (jpegFiles.length === 0 && pngFiles.length === 0) {
        console.log('\n⚠️  No images found to optimize.');
        console.log('   Searched: storage/app/public (always), and with "Include Public": public/frontend, public/front, public/images, public/assets.');
    }

    console.log('\n📊 Optimization Results:');
    console.log(`Original total size: ${(totalOriginalSize / 1024).toFixed(2)} KB`);
    console.log(`Optimized total size: ${(totalOptimizedSize / 1024).toFixed(2)} KB`);
    console.log(`Total saved: ${(totalSaved / 1024).toFixed(2)} KB (${savingsPercent}%)`);
    console.log(`\n💾 JPEG savings: ${(jpegTotalSaved / 1024).toFixed(2)} KB`);
    console.log(`💾 PNG savings: ${(pngTotalSaved / 1024).toFixed(2)} KB`);

    if (createWebP) {
        console.log(`\n📁 WebP versions created in: webp/ subdirectories (storage + public when included)`);
    }
    
    console.log(`📦 Storage images optimized.`);
    if (includePublic) {
        console.log(`🖼️  Public images optimized.`);
    }
    console.log('\n🎉 Image optimization complete!');
    console.log('⚠️  Original files have been overwritten with optimized versions.');
    if (createWebP) {
        console.log('💡 WebP versions are in webp/ subdirectories; use <picture> + get_image_webp() in views to serve them.');
    }
}

optimizeImages().catch(console.error);
