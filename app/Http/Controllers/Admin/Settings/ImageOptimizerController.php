<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Admin\AdminBaseController;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ImageOptimizerController extends AdminBaseController
{
    /**
     * Display the image optimizer page
     */
    public function index()
    {
        return view('admin.image-optimizer.index');
    }

    /**
     * Stream optimization output (SSE)
     */
    public function stream(Request $request)
    {
        return response()->stream(function () use ($request) {
            $scriptPath = base_path('scripts/optimize-images.js');

            if (!file_exists($scriptPath)) {
                echo "data: " . json_encode(['type' => 'error', 'data' => 'Optimization script not found']) . "\n\n";
                ob_flush();
                flush();
                return;
            }

            $command = ['node', $scriptPath];

            // Add flags based on request parameters
            if ($request->input('include-public') === 'true') {
                $command[] = '--include-public';
            }
            if ($request->input('no-webp') === 'true') {
                $command[] = '--no-webp';
            }

            $process = new Process($command, base_path(), null, null, null);

            $process->setTimeout(null);
            $process->start();

            $buffer = '';

            while ($process->isRunning()) {
                $output = $process->getIncrementalOutput();
                $errorOutput = $process->getIncrementalErrorOutput();

                if ($output) {
                    $buffer .= $output;
                    $lines = explode("\n", $buffer);
                    $buffer = array_pop($lines); // Keep incomplete line in buffer

                    foreach ($lines as $line) {
                        if (trim($line)) {
                            echo "data: " . json_encode(['type' => 'output', 'data' => $line]) . "\n\n";
                            ob_flush();
                            flush();
                        }
                    }
                }

                if ($errorOutput) {
                    echo "data: " . json_encode(['type' => 'error', 'data' => $errorOutput]) . "\n\n";
                    ob_flush();
                    flush();
                }

                usleep(100000); // Sleep for 100ms
            }

            $process->wait();

            // Drain any remaining stdout (e.g. "Optimization Results", "Total saved" summary lines
            // that Node may have buffered and only flushed when the process exited)
            $output = $process->getIncrementalOutput();
            if ($output) {
                $buffer .= $output;
                $lines = explode("\n", $buffer);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        echo "data: " . json_encode(['type' => 'output', 'data' => $line]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                }
            } elseif (trim($buffer) !== '') {
                echo "data: " . json_encode(['type' => 'output', 'data' => trim($buffer)]) . "\n\n";
                ob_flush();
                flush();
            }

            echo "data: " . json_encode(['type' => 'complete', 'exitCode' => $process->getExitCode()]) . "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}

